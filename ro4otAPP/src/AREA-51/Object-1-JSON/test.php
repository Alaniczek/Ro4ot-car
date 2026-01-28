<?php
$f = 'data.json';

if ($in = file_get_contents('php://input')) {
    // Dekodujemy i kodujemy ponownie z wcięciami
    file_put_contents($f, json_encode(json_decode($in), JSON_PRETTY_PRINT));
    exit;
}

$d = json_decode(@file_get_contents($f), true) ?? ['ip'=>'', 'rozkaz'=>''];
?>

<style>input{display:block;width:100%;margin:10px 0;padding:5px}</style>

<input id="ip" value="<?= $d['ip'] ?? '' ?>" oninput="s()" placeholder="IP">
<input id="rozkaz" value="<?= $d['rozkaz'] ?? '' ?>" oninput="s()" placeholder="Rozkaz">

<script>
function s() {
    fetch('', {
        method: 'POST',
        body: JSON.stringify({
            ip: document.getElementById('ip').value,
            rozkaz: document.getElementById('rozkaz').value
        })
    });
}
</script>