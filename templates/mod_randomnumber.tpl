<div style="border: 1px solid rgb(153, 153, 153); padding: 10px;">
    <form action="{{link_url::<?php echo $this->action; ?>}}#random_number" id="form_random_number" method="post">
	<input type="hidden" name="FORM_SUBMIT" value="random_number" />    
	<div><strong><?php echo $this->inputCharTypes->generateLabel();?>:</strong><?php echo $this->inputCharTypes->generate();?></div>
	<div><strong><?php echo $this->inputAnz->generateLabel();?>: </strong><?php echo $this->inputAnz->generate();?></div>
	<div style="text-align:right;"><input type="submit" name="senden" value="generieren"></div>
    </form>
        
    <div id="number_box" style="<?php if($this->randomNumber === 0): ?>display:none;<?php endif; ?>">
	<strong>generierte Zeichenfolge: </strong>
	<div style="font-size:18; font-weight:bold; color:#308F00; overflow:auto; width:600px;" id="generate_number"><?php  echo $this->randomNumber;?></div>
    </div>
    
</div>

