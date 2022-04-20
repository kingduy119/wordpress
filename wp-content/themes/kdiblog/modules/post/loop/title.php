<?php

is_single()
    ? the_title('<h1>', '</h1>')
    : the_title('<h2>', '</h2>');