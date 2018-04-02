<?php 

$data = $this->getDataForView();
$menue = $data['menue'];
include("header.php");
?>


<div class="container">

    <div class="card ">
        <div class="card-content">
          <span class="card-title">
            <?php if (isset($data["backButton"])) { ?>
                <a id="backButton" class="mdl-navigation__link waves-effect waves-light teal-text"
                   href="<?php echo $data["backButton"]; ?>"><i
                            class="material-icons">chevron_left</i></a>
            <?php } ?>
              <?php echo $data['header']; ?>
          </span>
            <form enctype="multipart/form-data" class="row"  method="post"
                  action="?type=<?php echo $data['actiontype']; ?>">
				  
				  
				  
				  
                <div class="file-field input-field col l12">
                    <div class="btn orange">
                        <span>Datei</span>
                        <input type="file" name="file" id="file" required>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Bitte wählen Sie eine Quelldatei">
                    </div>
                </div>
                <button class="btn-flat btn-large waves-effect waves-orange col l12" type="submit">
                    Submit
                    <i class="material-icons right">send</i>
                </button>
            </form>
            
        </div>

    </div>

</div>


<!-- Include Javascript -->
<script>
    window.top.window.uploadComplete("");
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<script>

    function submitFile(actionType) {
        // file has started loading
        alert("file");
    }

    function uploadComplete(success, error) {
        //file completed uploading

        if (!success) {
            Materialize.toast("Fehler beim Hochladen der Datei: " + error, 4000);
        }
        else {
            var student = <?php echo (\View::getInstance()->getDataForView()['action'] == "uschoose") ? "true" : "false"; ?>;
            var teacher = <?php echo (\View::getInstance()->getDataForView()['action'] == "utchoose") ? "true" : "false"; ?>;

            //var type = student ? "dispsupdate1" : "disptupdate1";
            var type;
            if (student) {
                type = "dispsupdate1";
            } else if (teacher) {
                type = "disptupdate1";
            } else {
                type = "dispupdateevents";
            }

            window.location = "?type=" + type;
        }

    }

</script>
</body>
</html>
