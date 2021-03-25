export default async (req, res) => {
  console.log(req.cookies);
  let data = null;
  try {
    let res = await fetch('http://auth.company.loc/user/self', {
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
    res.status(500).json(e);
    return;
  }
  res.status(200).json(data);
}
