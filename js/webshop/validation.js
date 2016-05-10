/*
François 20160510 - Validating input fields
 */

// Validation of PLZ, must be exactly 4 numerals
Validation.add('validate-plz', 'Bitte geben Sie eine 4-stellige PLZ ein.', function(v) {
    return Validation.get('IsEmpty').test(v) || /^\d{4}$/.test(v);
});