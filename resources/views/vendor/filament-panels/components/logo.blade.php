<?php

$logo = \App\Models\BaseSetting::value('logo') ?? 'logo.jpg';

?>


<img src="{{ asset('storage/'.$logo) }}" alt="Logo" class="h-10">