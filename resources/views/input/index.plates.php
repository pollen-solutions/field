<?php
/**
 * @var Pollen\Field\FieldTemplateInterface $this
 */
?>
<?php $this->label('before'); ?>

<?php $this->before(); ?>

<?php echo $this->partial('tag', [
    'tag'   => 'input',
    'attrs' => $this->get('attrs', []),
]); ?>

<?php $this->after();

$this->label('after');