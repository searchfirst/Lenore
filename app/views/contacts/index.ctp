<h2>Contact Us</h2>
<?php if(!empty($contact_content)):?>
<?php if(!empty($contact_content['Decorative'][0])) echo $mediaAssistant->mediaLink($contact_content['Decorative'][0],array('class'=>'deco'),'crop',true,null,'Section')?> 
<?php echo $textAssistant->htmlFormatted($contact_content['Section']['description'],$contact_content['Resource'],
	'Section',$mediaAssistant->generateMediaLinkAttributes($contact_content['Resource'],array('rel'=>'contactgallery')))?> 
<?php endif;?>
<?php $form->create(null)?>
<form action="<?php echo $html->url('/contact'); ?>" method="post" accept-charset="UTF-8">
<?php echo $form->input('Contact.name',array('size'=>'20','error'=>'You must give your name.'))?> 
<?php echo $form->input('Contact.email',array('type'=>'email','size'=>'20','error'=>'You must give a valid email address.'))?> 
<?php echo $form->input('Contact.telephone', array('type'=>'phone','size'=>'20','label'=>'Phone No.','error'=>'You must give a valid phone number.'))?> 
<?php echo $form->input('Contact.address',array('cols'=>'30','rows'=>'3'))?> 
<?php echo $form->input('Contact.enquiry',array('cols'=>'30','rows'=>'8','error'=>'You must give some brief information regarding your enquiry.'))?> 
<?php echo $form->submit('Send Email',array('div'=>false))?> 
</form>