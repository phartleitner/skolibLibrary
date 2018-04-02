<?php 

$data = $this->getDataForView();
$menue = $data['menue'];
include("header.php");

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<script src="./js/basic.js"></script>
<script>


function checkRadio() {
	if(!$('input[name=key_field]').is(':checked'))  { 
	throwMessage("Bitte Vergleichskriterium (Key) angeben!");
	}
}

</script>
<div class="container">

    <div class="card ">
        <div class="card-content">
          <span class="card-title">
            <?php if (isset($data["backButton"])) { ?>
                <a id="backButton" class="mdl-navigation__link waves-effect waves-light teal-text"
                   href="<?php echo $data["backButton"]; ?>"><i
                            class="material-icons">chevron_left</i></a>
            <?php } ?>
              <?php  echo $data['header']; ?>
          </span>
            <form action="?type=<?php echo $data['actiontype']; ?>" method="POST">
                <p>
                    Wählen Sie eine Zuordnung der Quelldaten zu den Zieldatenfeldern in der Datenbank
                </p>
                <div class="row">
                    <table width="50%" align="center">
                        <tbody>
                        <?php $i = 0; ?>
                        <?php foreach ($data['fileData'][0] as $d)
                        { ?>
                        <tr>
                            <td>
                                
                                
                                <?php echo $d ?>
                            </td>
                            <td class="input-field">
                                <select class="browser-default right" name="post_dbfield[]" title="Select a file"
                                        required>
                                    <option selected></option>
                                    <?php foreach ($data['fileData'][1] as $f) { ?>
                                        <option value="<?php echo $f; ?>"><?php echo $f; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
							<td>
							<input required type="radio" name="key_field" id="<?php echo "key"+$i; ?>" value="<?php echo $i; ?>" class="with-gap"/>
							<label for="<?php echo "key"+$i; ?>">Key</label>
							</td>
                            <?php $i++; } ?>
                        </tr>
                        </tbody>
                    </table>

                </div>
                <div class="row">


                    <button onClick="checkRadio()" class="btn-flat right waves-effect waves-teal" id="btn_login" type="submit">Submit<i
                                class="material-icons right">send</i></button>

                </div>

                <input type="hidden" name="file" value="<?php echo $data['fileName'] ?>"></input>
            </form>
        </div>

    </div>

</div>



</body>
</html>
