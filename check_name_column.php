<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
\ = app();
\->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

\ = DB::select('SHOW COLUMNS FROM stats WHERE Field = ?', ['name']);
print_r(\);
?>
