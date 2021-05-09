import Http from '@/http';

export async function getUser(cookie) {
    if (typeof cookie === 'undefined') {
        cookie = null;
    }

    let data = null;
    try {
        let res = await Http.get('/user', {
            headers: { cookie }
        });
        data = res.data;
    } catch (e) {
        console.log(e);
        return null;
    }

    return data;
}
