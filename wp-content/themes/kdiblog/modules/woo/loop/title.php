<?php

is_single()
    ? the_title('<h1 class="product--title">', '</h1>')
    : the_title('<h2 class="product--title">', '</h2>');