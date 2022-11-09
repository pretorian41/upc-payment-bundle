<?php


namespace UPCPaymentBundle\Service;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use UPC\UpcPaymentData;
use UPC\UpcSDK;
use UPCPaymentBundle\Form\PaymentType;
use UPCPaymentBundle\Model\PaymentInterface;


class UPCPayService
{
    public const DEFAULT_CURRENCY = '980';
    public const PAYMENT_SUCCESS = '000';
    public const DEFAULT_VERSION = 1;
    private UpcSDK $connection;
    private string $locale = 'uk';

    public function __construct(
        private ConnectionsConfigInterface $config,
        private FormFactoryInterface $formFactory,
    )
    {
    }

    public function getForm(string $action = null, string $paymentPurpose = null): FormInterface
    {
        return $this->formFactory->create(
            PaymentType::class,
            array_merge(
                $this->config->getBasePaymentConfig(),
                ['locale' => $this->locale],
                [
                    'PurchaseDesc' => $paymentPurpose
                ],
            ),
            [
                'action' => $action
            ]
        );
    }

    public function makePayment(PaymentInterface $payment, $data)
    {
        $postData = array_merge(
            $this->config->getBasePaymentConfig(),
            ['locale' => $this->locale],
            $data
        );
        $postData['OrderID'] = $payment->getId() . $payment->paymentSuffix(); //$payment instanceof UserPayment ? '_order' : '_payment');
        $postData['TotalAmount'] = (int)($payment->getAmount() * 100); //transform currency to cents
        $paymentData = new UpcPaymentData(
            $postData['MerchantID'],
            $postData['TerminalID'],
            $postData['PurchaseTime'],
            $postData['OrderID'],
            $postData['Currency'],
            $postData['TotalAmount']
        );
        $postData['Signature'] = $this->buildConnection()->signature($paymentData);

        return $postData;
    }

    private function buildConnection(): UpcSDK
    {
        return $this->connection
            ?? $this->connection = new UpcSDK(
                $this->config->getConnectionKey(),
                $this->config->getConnectionBaseUrl(),
                $this->config->isFile()
            );
    }

    public function checkRequestSignature(array $requestData): bool
    {
        $dataArray = [
            $requestData['MerchantID'],
            $requestData['TerminalID'],
            $requestData['PurchaseTime'],
            $requestData['OrderID'],
            $requestData['XID'],
            $requestData['Currency'],
            $requestData['TotalAmount'],
            $requestData['SD'],
            $requestData['TranCode'],
            $requestData['ApprovalCode'],
        ];
        $data = implode(';', $dataArray) . ';';
        $cert = $this->config->getSignatureKey();

        return 1 === openssl_verify(
                $data,
                base64_decode($requestData['Signature']),
                openssl_get_publickey($cert)
            );
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }
}
