<?php

namespace App\Services;

use App\Interfaces\EmailServiceInterface;
use App\Interfaces\InventoryServiceInterface;
use App\Interfaces\LoggerInterface;
use App\Interfaces\PaymentGatewayInterface;
use App\Models\Order;

class OrderService
{
    private PaymentGatewayInterface $paymentGateway;
    private EmailServiceInterface $emailService;
    private InventoryServiceInterface $inventoryService;
    private LoggerInterface $logger;

    public function __construct(
        PaymentGatewayInterface $paymentGateway,
        EmailServiceInterface $emailService,
        InventoryServiceInterface $inventoryService,
        LoggerInterface $logger
    ) {
        $this->paymentGateway = $paymentGateway;
        $this->emailService = $emailService;
        $this->inventoryService = $inventoryService;
        $this->logger = $logger;
    }

    /**
     * Process an order: check stock, charge payment, send confirmation
     *
     * TODO: Write tests for:
     * 1. Successful order processing (test behavior: methods called in correct order)
     * 2. Insufficient stock scenario (should not charge payment)
     * 3. Payment failure scenario (should release reserved stock)
     * 4. Email sending after successful payment
     *
     * Use setUp() and tearDown() appropriately
     * Create descriptive test names
     * Test behavior, not implementation
     */
    public function processOrder(Order $order, string $cardToken): bool
    {
        $this->logger->info('Processing order', ['order_id' => $order->getId()]);

        // Check and reserve stock for all items
        foreach ($order->getItems() as $item) {
            $availableStock = $this->inventoryService->checkStock($item->getProductId());

            if ($availableStock < $item->getQuantity()) {
                $this->logger->error('Insufficient stock', [
                    'product_id' => $item->getProductId(),
                    'required' => $item->getQuantity(),
                    'available' => $availableStock
                ]);
                return false;
            }

            $this->inventoryService->reserveStock(
                $item->getProductId(),
                $item->getQuantity()
            );
        }

        // Attempt payment
        $paymentSuccess = $this->paymentGateway->charge(
            $order->getTotalAmount(),
            $cardToken
        );

        if (!$paymentSuccess) {
            $this->logger->error('Payment failed', ['order_id' => $order->getId()]);

            // Release reserved stock
            foreach ($order->getItems() as $item) {
                $this->inventoryService->releaseStock(
                    $item->getProductId(),
                    $item->getQuantity()
                );
            }

            return false;
        }

        // Update order status
        $order->setStatus('confirmed');
        $order->setTransactionId('txn_' . uniqid());

        // Send confirmation email
        $this->emailService->sendOrderConfirmation(
            $order->getCustomerEmail(),
            $order
        );

        $this->logger->info('Order processed successfully', [
            'order_id' => $order->getId()
        ]);

        return true;
    }

    /**
     * Cancel an order and refund payment
     *
     * TODO: Write tests for:
     * 1. Successful cancellation with refund
     * 2. Cancellation when refund fails
     * 3. Verify stock is released back
     */
    public function cancelOrder(Order $order): bool
    {
        if ($order->getStatus() !== 'confirmed') {
            $this->logger->error('Cannot cancel order in current status', [
                'order_id' => $order->getId(),
                'status' => $order->getStatus()
            ]);
            return false;
        }

        // Refund payment
        $refundSuccess = $this->paymentGateway->refund($order->getTransactionId());

        if (!$refundSuccess) {
            $this->logger->error('Refund failed', ['order_id' => $order->getId()]);
            return false;
        }

        // Release stock
        foreach ($order->getItems() as $item) {
            $this->inventoryService->releaseStock(
                $item->getProductId(),
                $item->getQuantity()
            );
        }

        $order->setStatus('cancelled');

        return true;
    }
}