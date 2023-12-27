
const LANG_PARAM = 'lang';
const SELECTED_LANGUAGE = new URLSearchParams(window.location.search).get(LANG_PARAM);

function getLanguageOptions(data) {
    const options = [];

    data.forEach(lang => {
        const emoji = getFlagEmoji(lang.flag_code);

        const $opt = $('<option>')
            .text(`${emoji} ${lang.language_name}`)
            .attr('value', lang.language_code);
            
        options.push($opt);
    });

    return options;
}

function getLanguageRedirectUrl(language) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set(LANG_PARAM, language);

    const url = window.location.href.replace(window.location.search, '');
    return url + '?' + urlParams.toString();
}

/**
 * https://gomakethings.com/getting-emoji-from-country-codes-with-vanilla-javascript/
 * 
 * Get the flag emoji for the country
 * @link https://dev.to/jorik/country-code-to-flag-emoji-a21
 * @param {string} countryCode The country code
 * @returns {string} The flag emoji
 */
function getFlagEmoji(countryCode) {
	let codePoints = countryCode.toUpperCase().split('').map(char =>  127397 + char.charCodeAt());
	return String.fromCodePoint(...codePoints);
}