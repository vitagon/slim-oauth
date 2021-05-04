import Http from '@/http/authHttp';

export async function getUser(cookie) {
    let data = null;
    try {
        let res = await Http.get('/profile', {
            headers: { cookie }
        });
        data = res.data;
    } catch (e) {
        console.log(e);
        return null;
    }

    return data.user;
}
