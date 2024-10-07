<?php
require 'vendor/autoload.php';

use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output\Png;
use Chapa\Pix\Payload;

// Verifica se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomeEstabelecimento = $_POST['nomeEstabelecimento'];
    $valor = number_format($_POST['valor'], 2, '.', ''); // Formata o valor

    // Monta os dados Pix
    $pix = (new Payload())
        ->setKey('SEU_CODIGO_PIX') // Insira aqui a sua chave Pix
        ->setDescription('Recarga para ' . $nomeEstabelecimento)
        ->setMerchantName('Nome do seu negócio') // Nome do recebedor
        ->setMerchantCity('Sua cidade') // Cidade do recebedor
        ->setAmount($valor) // Valor da recarga
        ->setTransactionId('123456789'); // ID da transação (opcional)

    // Gera o código QR do Pix
    $qrCode = new QrCode($pix->getPayload());
    $output = new Png();

    // Caminho temporário para salvar a imagem do QR Code
    $qrCodePath = 'qrcodes/qrcode_' . time() . '.png';
    file_put_contents($qrCodePath, $output->output($qrCode, 300));

    // Redireciona de volta para a página principal com o QR Code gerado
    header('Location: index.php?qr_code=' . $qrCodePath);
    exit;
}
