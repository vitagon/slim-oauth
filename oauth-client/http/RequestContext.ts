class RequestContext {
    private static req = null;

    static init = (req) => {
        RequestContext.req = req;
    }

    static getRequest = () => {
        return RequestContext.req;
    }
}

export default RequestContext;
