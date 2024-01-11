const ID_TOKEN_KEY = "id_token" as string;
const USER = "user" as string;
const BACKGROUND_COLOR = "background_color" as string;

/**
 * @description get token form localStorage
 */
export const getToken = (): string | null => {
    return window.localStorage.getItem(ID_TOKEN_KEY);
};

/**
 * @description save token into localStorage
 * @param token: string
 */
export const saveToken = (token: string): void => {
    window.localStorage.setItem(ID_TOKEN_KEY, token);
};

/**
 * @description remove token form localStorage
 */
export const destroyToken = (): void => {
    window.localStorage.removeItem(ID_TOKEN_KEY);
};

/**
 * @description save background color into localStorage
 * @param background_color: string
 */
export const saveBackgroundColor = (background_color: string): void => {
    window.localStorage.setItem(BACKGROUND_COLOR, background_color);
};

/**
 * @description get background color form localStorage
 */
export const getBackgroundColor = (): string | null => {
    return window.localStorage.getItem(BACKGROUND_COLOR);
};

export default {
    getToken, 
    saveToken, 
    destroyToken, 
    saveBackgroundColor, 
    getBackgroundColor 
};