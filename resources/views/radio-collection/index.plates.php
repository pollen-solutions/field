<?php
/**
 * @var Pollen\Field\FieldTemplateInterface $this
 */
?>
<?php $this->before(); ?>

<nav <?php echo $this->htmlAttrs(); ?>>
    <?php $this->insert('choices', $this->all()); ?>
</nav>

<?php $this->after();