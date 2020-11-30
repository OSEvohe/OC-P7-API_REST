<?php


namespace App\Request\ParamConverter;


use App\Entity\Brand;
use App\Entity\Company;
use App\Entity\Product;
use App\Entity\User;
use App\Exception\ApiBadParameterException;
use App\Exception\ApiObjectNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class BilemoParamConverter implements ParamConverterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ProductParamConverter constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $class = $configuration->getClass();
        $name = $configuration->getName();

        if (true === is_numeric($request->get('id'))){
            $this->find($request, $class, $name);
        } else {
            throw new ApiBadParameterException("Bad Url Parameter, ".$this->getShortClassName($class) . " id must be an integer", null, 500);
        }
    }


    private function find(Request $request, $class, $name){
        $id = $request->get('id');
        if (null === $product = $this->em->getRepository($class)->find($id)) {
            throw new ApiObjectNotFoundException($this->getShortClassName($class) . " with id $id not found");
        }

        $request->attributes->set($name, $product);
    }

    public function supports(ParamConverter $configuration)
    {
        return false !== array_search($configuration->getClass(),
            [
                Product::class,
                Brand::class,
                Company::class,
                User::class
            ],
            true);
    }

    private function getShortClassName($class)
    {
        try {
            return (new ReflectionClass($class))->getShortName();
        } catch (ReflectionException $e) {
        }
    }


}