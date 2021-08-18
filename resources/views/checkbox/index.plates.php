<?php
/**
 * @var Pollen\Field\FieldTemplateInterface $this
 */
?>
<?php $this->layout($this->get('theme') . "-wrapper", $this->all()); ?>

<?php echo $this->field('input', [
    'attrs' => $this->get('attrs', []),
    'label' => false
]); ?>