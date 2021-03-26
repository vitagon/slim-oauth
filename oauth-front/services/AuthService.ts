import Http from '@/http';

export async function getUser(cookie) {
    let data = null;
    try {
        let res = await Http.get('/api/profile', {
            headers: { cookie }
        });
        data = res.data;
    } catch (e) {
        console.log(e);
        return null;
    }

    return data;
}
