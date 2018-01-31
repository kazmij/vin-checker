<?php

namespace App\AdminBundle\Command;

use App\AdminBundle\Entity\Manufacturer;
use App\AdminBundle\Entity\Model;
use App\AdminBundle\Entity\ModelTrim;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\DependencyInjection\Container;

class CarsCommand extends ContainerAwareCommand
{

    /**
     * @var \GuzzleHttp\Client
     */
    private $carsClient;

    private $proxyServer;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('get:cars')
            // the short description shown while running "php bin/console list"
            ->setDescription('Get cars');
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var Container */
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();

        $this->carsClient = new \GuzzleHttp\Client([
            'base_uri' => 'https://www.carqueryapi.com/api/0.3/',
            'verify' => false,
            'referer' => true,
            'timeout' => '30',
            'headers' => [
                'User-Agent' => 'Kazmijv1.0',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, br',
            ],
        ]);
        $cars = $this->getData(['cmd' => 'getMakes']);
        $trimRepository = $container->get('app.model_trim_repository');
        $manufacturerRepository = $container->get('app.manufacturer_repository');
        $modelRepository = $container->get('app.model_repository');
        if (isset($cars['Makes'])) {
            $cars = $cars['Makes'];

            foreach ($cars as $car) {
                $carManufacturer = $manufacturerRepository->findOneByName($car['make_display']);
                if(!$carManufacturer) {
                    $carManufacturer = new Manufacturer();
                    $carManufacturer
                        ->setName($car['make_display'])
                        ->setApiId($car['make_id'])
                        ->setCountry($car['make_country']);
                    $em->persist($carManufacturer);
                    $em->flush($carManufacturer);
                }

                if ($carManufacturer->getId() && $carManufacturer->getApiId()) {
                    $models = $this->getData(['cmd' => 'getModels', 'make' => $carManufacturer->getApiId()]);
                    if (isset($models['Models'])) {
                        $models = $models['Models'];
                        if (count($models)) {
                            foreach ($models as $model) {
                                $carModel = $modelRepository->findOneByName($model['model_name']);
                                if(!$carModel) {
                                    $carModel = new Model();
                                    $carModel
                                        ->setName($model['model_name'])
                                        ->setManufacturer($carManufacturer);
                                    $em->persist($carModel);
                                    $em->flush($carModel);
                                }

                                if ($carModel->getId()) {
                                    $trims = $this->getData(['cmd' => 'getTrims', 'model' => $carModel->getName()]);

                                    if (isset($trims['Trims'])) {
                                        foreach ($trims['Trims'] as $trim) {
                                            if ($trim['model_trim']) {
                                                $carModelTrim = $trimRepository->findOneBy([
                                                    'name' => $trim['model_trim'],
                                                    'model' => $carModel->getId(),
                                                    'year' => $trim['model_year']
                                                ]);
                                            } else {
                                                $carModelTrim = $trimRepository->findOneBy([
                                                    'model' => $carModel->getId(),
                                                    'year' => $trim['model_year']
                                                ]);
                                            }

                                            if(!$carModelTrim) {
                                                $carModelTrim = new ModelTrim();
                                                $carModelTrim
                                                    ->setName($trim['model_trim'])
                                                    ->setModel($carModel)
                                                    ->setYear($trim['model_year']);
                                                $em->persist($carModelTrim);
                                                $em->flush($carModelTrim);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function getProxy()
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://pubproxy.com/api/proxy?api=UTQ2a0lUNmlLNXBWY0RtMkU5SzNlZz09&format=json&last_check=60&limit=1&https=true&user_agent=true&referer=true',
            'verify' => false,
            'referer' => true,
            'headers' => [
                'User-Agent' => 'Kazmijv1.0',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, br',
            ]
        ]);

        $res = $client->request('GET', null, []);

        $proxies = json_decode($res->getBody()->getContents(), true);
        foreach ($proxies['data'] as $proxy) {
            $this->proxyServer = $proxy['ipPort'];
        }

        return true;
    }

    public function getData($requestData = [])
    {
        try {
            if (!$this->proxyServer) {
                $this->getProxy();
            }
            $res = $this->carsClient->request('GET', null, [
                'query' => $requestData,
                'proxy' => $this->proxyServer
            ]);

            return json_decode($res->getBody()->getContents(), true);

        } catch (\Exception $e) {
            $this->getProxy();

            return $this->getData($requestData);
        }
    }
}