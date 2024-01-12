<?php

namespace App\Controller;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Label\Font\NotoSans;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QrCodeController extends AbstractController
{

    /**
     * @var ResultInterface
     */
    private $result;

    public function __construct(BuilderInterface $customQrCodeBuilder)
    {
        $this->result = $customQrCodeBuilder
            ->writer(new PngWriter())
            ->writerOptions([])
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevel\ErrorCorrectionLevelHigh())
            ->margin(10)
            ->backgroundColor(new Color('255', '255', '255'))
            ->roundBlockSizeMode(new RoundBlockSizeMode\RoundBlockSizeModeMargin())
            ->logoPath(__DIR__ . '/assets/cat.png')
            ->logoResizeToHeight(30)
            ->logoResizeToWidth(30)
            ->logoPunchoutBackground(false)
            ->labelFont(new NotoSans(16))
            ->labelAlignment(new LabelAlignmentCenter());
    }

    public function qrCode(Request $request, $data): Response
    {

        if (!$data) {
            return (new Response())->setContent('No data');
        }

        $dataObject = json_decode(base64_decode($data), false);

        $text = $dataObject->data ?? '';
        $size = $dataObject->size ?? 300;

        $result = $this->result
            ->data($text)
            ->labelText($text)
            ->size($size)
            ->validateResult(false)
            ->build();

        return (new QrCodeResponse($result))
            ->setCache([
                'must_revalidate' => false,
                'no_cache' => false,
                'no_store' => false,
                'no_transform' => false,
                'public' => true,
                'private' => false,
                'proxy_revalidate' => false,
                'max_age' => 600,
                's_maxage' => 600,
                'immutable' => true,
                'last_modified' => new \DateTime(),
                'etag' => $data
            ]);
    }

    public function generateData(Request $request): Response
    {
        return (new Response())->setContent(json_encode(
            [
                'data' => base64_encode($request->getContent())
            ]
        ));
    }

}