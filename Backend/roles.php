<?php
declare(strict_types=1);

const ROLES_PERMITIDOS = ['invitado', 'cliente', 'admin'];
const ROL_POR_DEFECTO = 'cliente';

function esRolPermitido(string $rol): bool
{
    return in_array($rol, ROLES_PERMITIDOS, true);
}