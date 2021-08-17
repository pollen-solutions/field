<?php
/**
 * @var Pollen\Field\FieldTemplateInterface $this
 * @var Pollen\Field\Drivers\RadioCollection\RadioChoiceInterface $choice
 */
?>
<li class="FieldRadioCollection-choice">
    <?php echo $choice->render(); ?>

    <?php if ($children = $choice->getChildren()) : ?>
        <?php $this->insert('choices', ['choices' => $children]); ?>
    <?php endif; ?>
</li>
