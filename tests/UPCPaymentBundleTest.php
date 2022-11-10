<?php

namespace App\Tests;

use Symfony\Component\Form\Test\TypeTestCase;
use UPCPaymentBundle\Model\PaymentInterface;
use UPCPaymentBundle\Service\ConnectionsConfig;
use UPCPaymentBundle\Service\UPCPayService;

class UPCPaymentBundleTest extends TypeTestCase
{
    private const BASE_DIR = __DIR__ . '/test_keys/';

    private const BASE_URL = 'ecg.test.upc.ua';
    private const BASE_CONF = [
        'Version' => 1,
        'MerchantID' => '1752637',
        'TerminalID' => 'E7880437',
        'Currency' => '980',
    ];


    /**
     * @covers UPCPayService::getForm
     */
    public function testForm(): void
    {
        $service = $this->buildClass();
        $service->setLocale('en');
        $this->assertEquals(
            [
                "Version" => 1,
                "MerchantID" => "1752637",
                "TerminalID" => "E7880437",
                "Currency" => "980",
                "locale" => "en",
                "PurchaseDesc" => "test"
            ],
            $service->getForm(null, 'test')->getData()
        );
    }

    /**
     * @covers UPCPayService::makePayment
     */
    public function testSignature()
    {
        $service = $this->buildClass();
        $stub = $this->createStub(PaymentInterface::class);
        $stub->method('paymentSuffix')
            ->willReturn('_order');
        $stub->method('getAmount')
            ->willReturn(77.77);
        $stub->method('getId')
            ->willReturn(7777);

        $this->assertEquals(
            [
                "Version" => 1,
                "MerchantID" => "1752637",
                "TerminalID" => "E7880437",
                "Currency" => "980",
                "locale" => "uk",
                "PurchaseDesc" => "test",
                "PurchaseTime" => 221110164928,
                "OrderID" => "7777_order",
                "TotalAmount" => 7777,
                "Signature" => "dhaCvPzxM7CVVpAcbEShNANgbzi5yB1lhrOfS0mVjh2XTtTQlm9X4ArAbOZcV2KRrK1PzzWvwGgRra2a5cN1H0OfUkIvHe7lG4WxtOJpNdnm5iMvuiX3RhXv7hYk72FyZ6nOBa3RllvYRg+DNAz9aCE5jxT3OGSLBaFsv1dFvPM="
            ],
            $service->makePayment(
                $stub, array_merge($service->getForm(null, 'test')->getViewData(), ['PurchaseTime' => 221110164928])
            )
        );
    }

    /**
     * @covers UPCPayService::checkRequestSignature
     */
    public function testRequestSignature()
    {
        $service = $this->buildClass();
        $requestDate = [
            'MerchantID' => '1752637',
            'TerminalID' => 'E7880437',
            'TranCode' => '000',
            'Currency' => '980',
            'AltCurrency' => '',
            'ApprovalCode' => '712413',
            'OrderID' => '24333_order',
            'Signature' => 'HVSVOL5mFtIcm2D4Iqbrj92P05mx8pQOvA2qB0TJJcRBa1pl0x2dDPRuv3NpURcprVnUGEVX2owjvyPK9uY7UyxBNOMNf20CqTVv1QnQNOSoJQ/l7hIGCbtGvdvKMjzzvAHjlGNoKjZL4YHtezw/WlKyIhsAH/iKAwbNrfRlTVE=',
            'PurchaseTime' => '221110164928',
            'TotalAmount' => '130000',
            'AltTotalAmount' => '',
            'ProxyPan' => '499999******0011',
            'SD' => '',
            'XID' => '22111016-499558',
            'Rrn' => '231416581486',
            'locale' => 'en',
            'version' => '1'
        ];
        $this->assertTrue($service->checkRequestSignature($requestDate));
    }

    private function buildClass(): UPCPayService
    {
        return new UPCPayService(
            new ConnectionsConfig(
                file_get_contents(self::BASE_DIR . 'test.pem'),
                file_get_contents(self::BASE_DIR . 'test-server.pub'),
                self::BASE_URL,
                self::BASE_CONF,
                false
            ),
            $this->factory
        );
    }
}
