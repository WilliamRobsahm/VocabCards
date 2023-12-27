
/**
 * Result for API operations (such as adding/editing)
 * @typedef {object} ApiResult
 * @property {boolean} success
 * @property {string} info Information about the result (such as error message)
 */

/**
 * Result for API methods that return a single data object
 * @typedef {object} ApiObjectResult
 * @property {boolean} success
 * @property {string} info Information about the result (such as error message)
 * @property {object} object Fetched data object
 */

/**
 * Result for API methods that return an array of data
 * @typedef {object} ApiDataResult
 * @property {boolean} success
 * @property {string} info Information about the result (such as error message)
 * @property {object[]} data Fetched data array
 */

class API {

    static Config = {
        PATH: 'http://localhost:8080/webbutveckling/VocabCards/api/index.php',
        LOG_RESULTS: true,
    }
    /**
     * @param {string} moduleName Name of module (ex. Country, Album, Artist)
     * @param {string} methodName Name of method (ex. get, get_list, save)
     * @param {object|FormData} data API method parameters
     */
    static async #request(moduleName, methodName, data = {}) {

        const url = [this.Config.PATH, moduleName, methodName].filter(e => e).join("/");
        const logPath = url.replace(this.Config.PATH, 'api');

        // Convert FormData to object
        if(data instanceof FormData) {
            const formDataObject = {};
            data.forEach(function(value, key){
                formDataObject[key] = value;
            });
            data = formDataObject;
        }

        var parseResult = (r) => {
            try {
                return (typeof r == 'string' ? JSON.parse(r) :
                    r.hasOwnProperty('responseJSON') ? r.responseJSON : r);
            }
            catch {
                return r;
            }
        }

        return new Promise((resolve) => {
            $.ajax({ url, data })
            .then(r => {
                const result = parseResult(r);
                if(this.Config.LOG_RESULTS) console.log(logPath, result);
                resolve(result);
            })
            .catch(r => {
                const result = parseResult(r);
                if(this.Config.LOG_RESULTS) console.warn(logPath, result);
                alert(result.responseText || result.info || 'Oopsie');
                resolve(result);
            })
        })
    }

    static Languages = {
        /** 
         * @param {number} id Language ID
         * @returns {Promise<ApiObjectResult>} 
         * */
        get: (id) => this.#request('language', 'get', { id }),
        /** 
         * @returns {Promise<ApiDataResult>} 
         * */
        getList: () => this.#request('language', 'get_list'),
        /** 
         * @param {object} params
         * @param {number} [params.id] Language ID (Insert new language if unset)
         * @param {string} params.language_name Language Name (ex. English)
         * @param {string} params.language_code Language Code (ex. EN, AR, DE)
         * @param {string} params.flag_code Flag code (ex. US)
         * @returns {Promise<ApiResult>} 
         * */
        save: (params) => this.#request('language', 'save', params),
        /**
         * @param {string} value Search value
         * @returns {Promise<ApiDataResult>}
         */
        search: (value) => this.#request('language', 'search', { value }),
    }

    static Cards = {
        /** 
         * @param {number} id Card ID
         * @returns {Promise<ApiObjectResult>} 
         * */
        get: (id) => this.#request('card', 'get', { id }),
        /** 
         * @returns {Promise<ApiDataResult>} 
         * */
        getList: () => this.#request('card', 'get_list'),
        /**
         * @param {string} lang Language code
         * @returns {Promise<ApiDataResult>} 
         */
        getForLanguage: (lang) => this.#request('card', 'get_for_language', { lang }),
        /** 
         * @param {object} params
         * @returns {Promise<ApiResult>} 
         * */
        save: (params) => this.#request('card', 'save', params),
    }

    static Definitions = {
        /** 
         * @param {number} id Definition ID
         * @returns {Promise<ApiObjectResult>} 
         * */
        get: (id) => this.#request('definition', 'get', { id }),
        /** 
         * @returns {Promise<ApiDataResult>} 
         * */
        getList: () => this.#request('definition', 'get_list'),
        /**
         * @param {string} card_id
         * @returns {Promise<ApiDataResult>} 
         */
        getForCard: (card_id, lang) => this.#request('definition', 'get_for_card', { card_id, lang }),
        /**
         * @param {string} lang Language code
         * @returns {Promise<ApiDataResult>} 
         */
        getForLanguage: (card_lang, lang) => this.#request('definition', 'get_for_language', { card_lang, lang }),
        /** 
         * @param {object} params
         * @returns {Promise<ApiResult>} 
         * */
        save: (params) => this.#request('definition', 'save', params),
    }
}

