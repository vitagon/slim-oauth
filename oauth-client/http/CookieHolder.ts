import Cookies from 'cookies';

class CookieHolder {
    private static cookies: Cookies = null;

    static init = (req, res) => {
        CookieHolder.cookies = new Cookies(req, res)
    }

    static get = (name: string): string => {
        return CookieHolder.cookies.get(name);
    }

    static set = (name: string, value: string): void => {
        CookieHolder.cookies.set(name, value);
    }

}

export default CookieHolder;