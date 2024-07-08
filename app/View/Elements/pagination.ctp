
<?php if ($this->Paginator->hasPrev() || $this->Paginator->hasNext()): ?>
<div class="pagination">
	<ul>
		<?php if ($this->Paginator->hasPrev()) : ?>
		<li><?php echo $this->Paginator->prev('« Prev', array(), null, array('class'=>'disabled'));?></li>
		<?php endif; ?>
		<?php if (isset($show_numbers) && $show_numbers): ?>
		<li><?php echo $this->Paginator->numbers(array(
			'separator' => false
		)); ?></li>
		<?php endif; ?>
		<?php if ($this->Paginator->hasNext()) : ?>
			<li><?php echo $this->Paginator->next('Next » ', array(), null, array('class' => 'disabled'));?></li>
		<?php endif; ?>
	</ul>
</div>
<?php endif; ?>