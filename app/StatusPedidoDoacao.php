<?php

namespace App;

enum StatusPedidoDoacao: string
{
    case PENDENTE = 'Pendente';
    case EM_PROCESSO = 'Doado em parte';
    case FINALIZADO = 'Doação Completa';
}
