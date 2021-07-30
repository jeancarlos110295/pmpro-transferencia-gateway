<?php

if( !function_exists("trans_after_before_trans_check")):
    /**
     * Imprime información de transferencia, cuando el cliente da en adquirir plan y cuando finaliza el pago.
     * 
     * @param $gateway type string
     * @param $cuentas type array
     * 
     * @return void
     */
    function trans_after_before_trans_check( string $gateway){
        ?>
        <section class="woocommerce-bacs-bank-details">
            <h2 class="wc-bacs-bank-details-heading">
                <?php echo(_e('Nuestros detalles bancarios', PMPRO_PLUGIN_NAME)); ?> 
            </h2>

            <h3 class="wc-bacs-bank-details-account-name">
                <strong> <?php echo(esc_attr(pmpro_getOption($gateway."_nombre"))); ?>: </strong>
            </h3>

            <blockquote>
                <strong> <?php echo(esc_attr(pmpro_getOption($gateway."_instructions"))); ?>: </strong>
            </blockquote>
        
            <ul class="wc-bacs-bank-details order_details bacs_details">
                <li class="bank_name">
                    <?php echo(_e('Banco:', PMPRO_PLUGIN_NAME)); ?> <strong> <?php echo(esc_attr(pmpro_getOption($gateway."_banco"))); ?> </strong>
                </li>
                
                <li class="account_number">
                    <?php echo(_e('Número de cuenta:', PMPRO_PLUGIN_NAME)); ?> <strong> <?php echo(esc_attr(pmpro_getOption($gateway."_cuenta"))); ?> </strong>
                </li>
                
                <li class="sort_code">
                    <?php echo(_e('Tipo de Cuenta:', PMPRO_PLUGIN_NAME)); ?> <strong> <?php echo(esc_attr(transGetTiposCuentas()[pmpro_getOption($gateway."_tipo")])); ?> </strong>
                </li>
                
                <li class="iban">
                    <?php echo(_e('IBAN/RUT:', PMPRO_PLUGIN_NAME)); ?> <strong> <?php echo(esc_attr(pmpro_getOption($gateway."_iban"))); ?> </strong>
                </li>
                
                <li class="bic">
                    <?php echo(_e('Correo:', PMPRO_PLUGIN_NAME)); ?> <strong> <?php echo(esc_attr(pmpro_getOption($gateway."_email"))); ?> </strong>
                </li>
            </ul>
        </section>

    <?php
    }
endif;


if( !function_exists("transGetTiposCuentas")):
    /**
     * Retornar los tipos de cuenta.
     * 
     * @return array
     */
    function transGetTiposCuentas(): array{
        $tiposDeCuentas = array(
            "Cuenta corriente",
            "Cuenta de ahorro",
            "Cuenta vista",
            "Cuenta Chequera Electrónica",
            "Cuenta RUT"
        );
    
        return $tiposDeCuentas;
    }
endif;