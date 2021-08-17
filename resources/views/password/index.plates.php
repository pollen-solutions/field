<?php
/**
 * @var Pollen\Field\FieldTemplateInterface $this
 */
?>
<?php $this->label('before'); ?>

<?php $this->before(); ?>

<?php echo $this->field('input', [
    'attrs' => $this->get('attrs', []),
    'label' => false
]); ?>

<?php $this->after();

$this->label('after');