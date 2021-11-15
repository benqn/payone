<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Shared\Payone\PayoneConstants;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceSubForm extends AbstractPayoneSubForm
{
    /**
     * @var string
     */
    public const PAYMENT_METHOD = 'invoice';

    /**
     * @return string
     */
    public function getName(): string
    {
        return PaymentTransfer::PAYONE_INVOICE;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return PaymentTransfer::PAYONE_INVOICE;
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return PayoneConstants::PROVIDER_NAME . '/' . static::PAYMENT_METHOD;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PayonePaymentTransfer::class,
        ])->setRequired(SubFormInterface::OPTIONS_FIELD_NAME);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolver $resolver): void
    {
        $this->configureOptions($resolver);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addLabel($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLabel(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_PAYMENT_METHOD,
            HiddenType::class,
            [
                'label' => false,
                'required' => false,
                'data' => [],
            ],
        );

        return $this;
    }
}
