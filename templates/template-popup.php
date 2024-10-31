<?php
$doc_types = custom_woo_builder()->documents->get_document_types();
?>
<div class="custom-template-popup">
	<div class="custom-template-popup__overlay"></div>
	<div class="custom-template-popup__content">
		<h3 class="custom-template-popup__heading"><?php
			esc_html_e( 'Add Template', 'custom-woo-builder' );
		?></h3>
		<form class="custom-template-popup__form" method="POST" action="<?php echo $action; ?>" >
			<div class="custom-template-popup__form-row plain-row">
				<label for="template_type"><?php esc_html_e( 'This template for:', 'custom-woo-builder' ); ?></label>
				<select id="template_type" name="template_type"><?php
					foreach ( $doc_types as $type ) {
						printf(
							'<option value="%1$s">%2$s</option>',
							$type['slug'],
							$type['name']
						);
					}
				?></select>
			</div>
			<div class="custom-template-popup__form-row plain-row">
				<label for="template_name"><?php esc_html_e( 'Template Name:', 'custom-woo-builder' ); ?></label>
				<input type="text" id="template_name" name="template_name" placeholder="<?php esc_html_e( 'Set template name', 'custom-woo-builder' ); ?>">
			</div>
			<h4 class="custom-template-popup__subheading"><?php
				esc_html_e( 'Start from Layout', 'custom-woo-builder' );
			?></h4>
			<div class="custom-template-popup__form-row predesigned-row template-<?php echo $doc_types['single']['slug']; ?> is-active"><?php
				foreach ( $this->predesigned_single_templates() as $id => $data ) {
					?>
					<div class="custom-template-popup__item">
						<label class="custom-template-popup__label">
							<input type="radio" name="template_single" value="<?php echo $id; ?>">
							<img src="<?php echo $data['thumb']; ?>" alt="">
						</label>
						<span class="custom-template-popup__item--uncheck"><span>×</span></span>
					</div>
					<?php
				}
			?></div>
			<div class="custom-template-popup__form-row predesigned-row template-<?php echo $doc_types['archive']['slug']; ?>"><?php
				foreach ( $this->predesigned_archive_templates() as $id => $data ) {
					?>
					<div class="custom-template-popup__item">
						<label class="custom-template-popup__label">
							<input type="radio" name="template_archive" value="<?php echo $id; ?>">
							<img src="<?php echo $data['thumb']; ?>" alt="">
						</label>
						<span class="custom-template-popup__item--uncheck"><span>×</span></span>
					</div>
					<?php
				}
			?></div>
			<div class="custom-template-popup__form-row predesigned-row template-<?php echo $doc_types['category']['slug']; ?>"><?php
		  foreach ( $this->predesigned_category_templates() as $id => $data ) {
			  ?>
						<div class="custom-template-popup__item">
							<label class="custom-template-popup__label">
								<input type="radio" name="template_category" value="<?php echo $id; ?>">
								<img src="<?php echo $data['thumb']; ?>" alt="">
							</label>
							<span class="custom-template-popup__item--uncheck"><span>×</span></span>
						</div>
			  <?php
		  }
		  ?></div>
			<div class="custom-template-popup__form-row predesigned-row template-<?php echo $doc_types['shop']['slug']; ?>">
				<div class="predesigned-templates__description"><?php esc_html_e( 'For creating this template , you need combine shop template and archive template in custom Woo Builder settings', 'custom-woo-builder' ); ?></div>
					<?php
		  foreach ( $this->predesigned_shop_templates() as $id => $data ) {
			  ?>
						<div class="custom-template-popup__item">
							<label class="custom-template-popup__label">
								<input type="radio" name="template_shop" value="<?php echo $id; ?>">
								<img src="<?php echo $data['thumb']; ?>" alt="">
							</label>
							<span class="custom-template-popup__item--uncheck"><span>×</span></span>
						</div>
			  <?php
		  }
		  ?></div>
			<div class="custom-template-popup__form-actions">
				<button type="submit" id="templates_type_submit" class="button button-primary button-hero"><?php
					esc_html_e( 'Create Template', 'custom-woo-builder' );
				?></button>
			</div>
		</form>
	</div>
</div>