<?php

namespace Fastwf\Tests\Utils;

use Fastwf\Api\Http\Frame\HttpRequestInterface;
use Fastwf\Api\Utils\ArrayProxy;
use Fastwf\Form\Utils\RequestUtil;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\UploadedFile;
use GuzzleHttp\Psr7\ServerRequest;
use stdClass;

class RequestUtilTest extends TestCase
{

    private $profile;
    private $mapA;
    private $mapB;
    private $docA;
    private $docB;

    private $post;
    private $files;

    private $expected;

    protected function setUp(): void
    {
        $this->profile = new UploadedFile('/tmp/random1', 1024, UPLOAD_ERR_OK, 'pic1.jpg', 'image/jpeg');
        $this->mapA = new UploadedFile('/tmp/random2', 1024, UPLOAD_ERR_OK, 'pic2.jpg', 'image/jpeg');
        $this->mapB = new UploadedFile('/tmp/random3', 1024, UPLOAD_ERR_OK, 'pic3.jpg', 'image/jpeg');
        $this->docA = new UploadedFile('/tmp/random4', 1024, UPLOAD_ERR_OK, 'pic4.jpg', 'image/jpeg');
        $this->docB = new UploadedFile('/tmp/random5', 1024, UPLOAD_ERR_OK, 'pic5.jpg', 'image/jpeg');

        $this->post = [
            'profile' => [
                'name' => 'string',
                'email' => 'test@test.fr'
            ],
            'address' => [
                'main' => [
                    'city' => 'string',
                    'street' => 'string'
                ],
            ],
        ];
        $this->files = [
            'profile' => [
                'picture' => $this->profile
            ],
            'address' => [
                'main' => [
                    'map' => $this->mapA
                ],
                'secondary' => [
                    'map' => $this->mapB
                ]
            ],
            0 => $this->docA,
            1 => $this->docB,
        ];

        $this->expected = [
            'profile' => [
                'name' => 'string',
                'email' => 'test@test.fr',
                'picture' => $this->profile
            ],
            'address' => [
                'main' => [
                    'city' => 'string',
                    'street' => 'string',
                    'map' => $this->mapA
                ],
                'secondary' => [
                    'map' => $this->mapB
                ]
            ],
            0 => $this->docA,
            1 => $this->docB,
        ];
    }

    /**
     * @covers Fastwf\Form\Utils\RequestUtil
     */
    public function testDataFromRequest()
    {
        /** @var HttpRequestInterface */
        $request = new stdClass();

        $request->form = new ArrayProxy($this->post);
        $request->files = new ArrayProxy($this->files);

        $this->assertEquals($this->expected, RequestUtil::dataFromRequest($request));
    }

    /**
     * @covers Fastwf\Form\Utils\RequestUtil
     */
    public function testDataFromPsr7()
    {
        $request = (new ServerRequest('POST', '/'))
            ->withParsedBody($this->post)
            ->withUploadedFiles($this->files)
            ;

        $this->assertEquals($this->expected, RequestUtil::dataFromPsr7($request));
    }

}
