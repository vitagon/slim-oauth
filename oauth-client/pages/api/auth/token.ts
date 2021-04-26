import Http from '@/http';

export default async (req, res) => {
    let removeToken = false;
    let cookie = null;
    if (typeof req.headers.cookie !== 'undefined') {
        cookie = req.headers.cookie;
    }

    let data = null;
    try {
        res = await Http.get('/auth/user', {
            headers: {
                'Cookie': cookie
            }
        });
        data = res.data;
    } catch (e) {
        res.status(500).json(e);
    }

    res.status(200).json(data);

    try {
        let res = await fetch('http://company.loc/user/self', {
            headers: {
                'Accept': 'application/json',
                'Cookie': ''
            },
            credentials: 'include',
        });
        data = await res.text();

        // if (!res.ok) {
        //   let err = new Error(res.statusText);
        //   throw err;
        // }
    } catch (e) {
    }

}
