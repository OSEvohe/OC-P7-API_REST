<?php


namespace App\Request\ParamConverter;


use App\Entity\Brand;
use App\Entity\Company;
use App\Entity\Product;
use App\Entity\User;
use App\Exception\ApiBadParameterException;
use App\Exception\ApiObjectNotFoundException;
use App\Util\UtilHelper;
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
     * @var UtilHelper
     */
    private $utilHelper;

    /**
     * ProductParamConverter constructor.
     * @param EntityManagerInterface $em
     * @param UtilHelper $utilHelper
     */
    public function __construct(EntityManagerInterface $em, UtilHelper $utilHelper)
    {
        $this->em = $em;
        $this->utilHelper = $utilHelper;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $class = $configuration->getClass();
        $name = $configuration->getName();

        if (true === is_numeric($request->get('id'))){
            $this->find($request, $class, $name);
        } else {
            throw new ApiBadParameterException("Bad Url Parameter, ".$this->utilHelper->getShortClassName($class) . " id must be an integer", null, 500);
        }

        return true;
    }


    private function find(Request $request, $class, $name){
        $id = $request->get('id');
        if (null === $entity = $this->em->getRepository($class)->find($id)) {
            throw new ApiObjectNotFoundException($this->utilHelper->getShortClassName($class) . " with id $id not found");
        }

        $request->attributes->set($name, $entity);
    }

    public function supports(ParamConverter $configuration): bool
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
}