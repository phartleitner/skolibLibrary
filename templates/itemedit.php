 <div class="card" id="item-edit-details" class="card-panel grey lighten-2" style="display:none">
        <div class="card-content">
			
			  <span class="card-title" id="header">
				<span class="right" id="custombuttons">
				</span>
			  </span>
			<form autocomplete="off" onsubmit="saveChanges()" action="javascript:void(0);" class="row"
				  style="margin: 20px;">
			<div class="row"  >
				<div class="input-field col l12 m12 s12" >
					<input type="text" name="titel" id="titel" value=null required >
					<label for="text" class="truncate">Titel</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col l12 m12 s12">
					<input type="text" name="autor" id="autor" value=null>
					<label for="autor" class="truncate">Autor/Hrsg.</label>
				</div>
			</div>
			<div class="row">
				<div class="select col l6 m6 s6" style="margin-top: 10px">
					<label for="hkat">Kategorie
					<select class="browser-default" id="hkat" name="hkat" required >
					<option value="">Kategorie wählen</option>
					<?php 
					foreach($data['dropdown'][ $hkatData['dwNr'] ] as $key => $value) {
					?>
					<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
					<?php
					}
					?>
					</select>
					</label>
				</div>
				<div class="select col l6 m6 s6" style="margin-top: 10px">
					<label for="hkat">Medium
					<select class="browser-default" id="mtyp" name="mtyp" required >
					<option value="">Medium wählen</option>
					<?php 
					foreach($data['dropdown'][ $mtypData['dwNr'] ] as $key => $value) {
					?>
					<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
					<?php
					}
					?>
					</select>
					</label>
				</div>
			</div>
			<div class="row">
				<div class="select col l6 m6 s6" style="margin-top: 10px">
					<label for="ukata">Unterkategorie1
					<select class="browser-default" id="ukata" name="ukata" >
					<option value="">Unterkategorie1 wählen</option>
					<?php 
					foreach($data['dropdown'][ $ukat1Data['dwNr'] ] as $key => $value) {
					?>
					<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
					<?php
					}
					?>
					</select>
					</label>
				</div>
				<div class="select col l6 m6 s6" style="margin-top: 10px">
					<label for="ukatb">Unterkategorie2
					<select class="browser-default" id="ukatb" name="ukatb" >
					<option value="">Unterkategorie2 wählen</option>
					<?php 
					foreach($data['dropdown'][ $ukat2Data['dwNr'] ] as $key => $value) {
					?>
					<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
					<?php
					}
					?>
					</select>
					</label>
				</div>
			</div>
			<div class="row">
				<div class="select col l12 m12 s12" style="margin-top: 10px">
					<label for="swort">Schlagworte
					<textarea rows="2" id="swort" class="materialize-textarea" name="swort" ></textarea>
					</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col l12 m12 s12">
					<input type="text" name="zusatz" id="zusatz" value=null>
					<label for="zusatz" class="truncate">Sonstiges</label>
				</div>
			</div>
			
			<div class="row">
			
			<span align="center" class = "red-text" id="serie"></span>
				
			<button onClick="" class="btn-flat right waves-effect waves-teal right" id="btn_login" type="submit">
			Speichern</button>
			<a onClick="showItemInfoHTML()" class="btn-flat right waves-effect waves-teal right" id="btn_login" >
			Abbrechen</a>
			</div>
		
			</form>
			
		</div>
    </div>