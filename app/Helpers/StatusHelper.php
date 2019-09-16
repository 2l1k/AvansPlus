<?php

namespace App\Helpers;


class StatusHelper
{
//    статусы
    const APP_FILLED_BY_CLIENT         = 1;
    const LOADING_DOCUMENTS            = 2;
    const DOCUMENTS_UPLOADED_BY_CLIENT = 3;
    const LOADING_CONTRACT             = 4;
    const CONTRACT_UPLOADED_BY_CLIENT  = 7;
    const CONFIRMATION_APPLICATION     = 8;
    const APPLICATION_CONFIRMED        = 9;
    const TO_ISSUE                     = 10;
    const LOAN_ISSUED                  = 11;
    const LOAN_PAID                    = 12;
    const LOAN_EXTENDED                = 13;
    const LOAN_IS_OVERDUE              = 14;
    const NOTARIZED                    = 15;
    const CLOSED                       = 18;
    const TRANSFERRED_TO_COURT         = 19;
    const CLOSED_BY_COURT_ORDER        = 20;
    const REFUSED                      = 21;
    const PAID_BY_NOTARIAL_SIGNATURE   = 22;

//    категории статусов
    const CATEGORY_NEW      = 1;
    const CATEGORY_APPROVED = 2;
    const CATEGORY_CLOSED   = 3;
    const CATEGORY_REJECTED = 4;

}