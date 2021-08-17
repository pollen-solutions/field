<?php
/**
 * @var Pollen\Field\FieldTemplateInterface $this
 */
?>
<?php $this->label('before'); ?>

<?php $this->before(); ?>

<?php echo $this->partial('tag', [
    'tag'     => 'textarea',
    'attrs'   => $this->get('attrs', []),
    'content' => $this->get('content'),
]); ?>

<?php $this->after();

$this->label('after');