import Http from '@/http';

export async function getUser(cookie) {
    if (typeof cookie === 'undefined') {
        cookie = null;
    }

    let data = null;
    try {
        let res = await Http.get('/auth/user', {
            headers: { cookie }
        });
        data = res.data;
    } catch (e) {
        let r = refreshToken(cookie);
        console.log(e);
        return null;
    }

    return data;
}

export async function refreshToken(cookie) {
    let res = null;
    try {
        let res = await Http.post('/auth/refresh-token', {}, {
            headers: { cookie }
        });
    } catch (e) {
        console.log(e);
        return null;
    }

    return res;
}

// export async function _getUser() {
//     let data = null;
//     try {
//         let res = await Http.get('/auth/user', {
//             headers: { cookie }
//         });
//         data = res.data;
//     } catch (e) {
//         let r = refreshToken(cookie);
//         console.log(e);
//         return null;
//     }
// }