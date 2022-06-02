<div class="col">

    <div class="card border-0">
        <div class="row g-0">
            <div class="col-md-4">
                <?php
                    get_template_part( 'modules/loop/thumbnail' );
                ?>
            </div>
            <div class="col-md-8">
                <div class="card-body px-2 py-0">
                    <?php
                        get_template_part( 'modules/loop/title' );
                        get_template_part( 'modules/loop/author', '', array( 'show_avatar' => false ) );
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>