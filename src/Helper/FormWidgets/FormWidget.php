<?php

namespace Hgabka\AdminBundle\Helper\FormWidgets;

use Doctrine\ORM\EntityManager;
use Hgabka\UtilsBundle\Helper\FormHelper;
use Hgabka\UtilsBundle\Helper\FormWidgets\Tabs\TabInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

/**
 * The default tab implementation.
 */
class FormWidget implements FormWidgetInterface
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var AbstractType[]
     */
    protected $types;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var FormHelper
     */
    private $formHelper;

    /**
     * @param array $types The types
     * @param array $data  The data attached to the types
     */
    public function __construct(array $types = [], array $data = [], array $options = [])
    {
        $this->types = $types;
        $this->data = $data;
        $this->options = $options;

        $this->setTemplate('@HgabkaAdmin/FormWidgets/FormWidget/widget.html.twig');
    }

    /**
     * @param FormBuilderInterface $builder The form builder
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $data = $builder->getData();

        foreach ($this->types as $name => $type) {
            $builder->add($name, $type, $this->options[$name]);
            $data[$name] = $this->data[$name];
        }

        $builder->setData($data);
    }

    /**
     * @param Request $request
     */
    public function bindRequest(Request $request)
    {
    }

    /**
     * @param EntityManager $em
     */
    public function persist(EntityManager $em)
    {
        foreach ($this->data as $item) {
            $em->persist($item);
        }
    }

    /**
     * @param FormView $formView
     *
     * @return array
     */
    public function getFormErrors(FormView $formView)
    {
        $formViews = [];
        foreach ($this->types as $name => $type) {
            $formViews[] = $formView[$name];
        }

        $formHelper = $this->getFormHelper();

        return $formHelper->getRecursiveErrorMessages($formViews);
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $identifier
     *
     * @return TabInterface
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $name
     * @param string $type
     * @param null   $data
     * @param array  $options
     *
     * @return FormWidget
     */
    public function addType($name, $type, $data = null, $options = [])
    {
        // Get fully qualified class name of form if not provided as string
        if ($type instanceof AbstractType) {
            $type = \get_class($type);
        }
        $this->types[$name] = $type;
        $this->data[$name] = $data;
        $this->options[$name] = $options;

        return $this;
    }

    /**
     * @return AbstractType[]
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getExtraParams(Request $request)
    {
        return [];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return FormHelper
     */
    protected function getFormHelper()
    {
        if (null === $this->formHelper) {
            $this->formHelper = new FormHelper();
        }

        return $this->formHelper;
    }
}
