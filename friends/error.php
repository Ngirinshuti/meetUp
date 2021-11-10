<?php 
if (isset($_GET['Success'])) {
?>
    <p id="success"><?php echo $_GET["Success"];?>
        <span style="color: red; background: white;cursor: pointer;" onclick="this.parentElement.style.display='none';">&times;</span>
    </p>;
<?php
}
if (isset($_GET['Info'])) {
?>
    <p id="info"><?php echo $_GET["Info"];?>
        <span style="color: red; background: white;cursor: pointer;" onclick="this.parentElement.style.display='none';">&times;</span>
    </p>;
<?php
}
if (isset($_GET['Error'])) {
?>
    <p id="error"><?php echo $_GET["Error"];?>
        <span style="color: red; background: white;cursor: pointer;" onclick="this.parentElement.style.display='none';">&times;</span>
    </p>;
<?php
}
?>