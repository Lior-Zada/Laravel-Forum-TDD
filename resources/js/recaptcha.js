

let onSubmit = (token) => {
    document.getElementById("demo-form").submit();
}

module.exports = [
    onSubmit,
    reCAPTCHA_site_key,
];