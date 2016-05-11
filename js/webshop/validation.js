/*
François 20160510 - Validating input fields
 */

// Validation of PLZ, must be exactly 4 numerals
Validation.add('validate-ch-plz', 'Bitte geben Sie eine 4-stellige PLZ ein.', function(v) {
    return Validation.get('IsEmpty').test(v) || /^\d{4}$/.test(v);
});
Validation.add('validate-ch-phone', 'Bitte geben Sie eine korrekte Telefonnummer ein.', function(v) {
    return Validation.get('IsEmpty').test(v) || /^\d{10}$/.test(v);
});