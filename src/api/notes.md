

    Cookie

    destroy (array)
        param   (array) name, value, expires, path, domain, secure, httponly
        return  TRUE    if cookie was unset
                FALSE   if cookie doesn't exist

    validate (string)
        param   (string) cookie name
        return  TRUE    if Cookie exists
                FALSE   if cookie doesn't exist

    set (array)
        param   (array) name, value, expires, path, domain, secure, httponly
        return  TRUE    if cookie was set
                FALSE   if cookie was not set

*******************************************************************************

    File

    __construct (array)
        desc    Construct and initialize File object
                Fatal error otherwise
        param   (array)
                    file (string) with absolute path to file
                    autoBackup (boolean)
        return  (object)

    write (string)
        desc    Write data to file
        param   (string) data to be written
        return  (int) bytes written
                NULL if failed

    backup
        desc    Create backup file with current time
        return  (string) of backup-timestamp created
                NULL if failed to backup

    restore (string)
        desc    Restore to backup
        param   (string) timestamp of backup to restore
        return  TRUE if restored
                FALSE if failed
                NULL if backup does not exist

    getBackups
        desc    Retrieve all available backups
        return  (array) of string timestamps of backups found
                NULL if no backups present

    get
        desc    Get all data
        return  (string) of file data
                NULL if file is unreadable

*******************************************************************************

    JsonFile (File)

    write       inherit
    backup      inherit
    restore     inherit
    getBackups  inherit
    get         inherit

    __construct (array)
        desc    Construct parent, then initialize JsonFile
                Fatal error otherwise
        param   (array) must have following keys
                    file (string) absolute path to json-file
                    autoBackup (boolean)

    first
        desc    Get first item of last operation
        return  (object) first item in result of last operation
                NULL if no results

    get
        desc    Get all items of last operation
        return  (array) all items in results of last operation
                NULL if no results, or no operation was performed

    put (object)
        desc    Append object to file
        param   (object) object to be written to file
        return  (int) of bytes written
                NULL if operation failed

    where (string, mixed, string)
        param   (string) key of record
                (mixed)  value to be matched
                (string) comparision operation (==, !=, <=, >=, <, >)
        return  (object) returns self

*******************************************************************************

    Result (Singleton, Middleware)

    init        inherit
    enable      inherit
    disable     inherit
    boot        inherit

    middleware (RequestInterface, ResponseInterface, function)
        desc    Middleware function to be used with Slim
                Set HTTP response code
                Set Content-Type to application/json
                Write response data
        return  ResponseInterface

    success (int = 200, object = NULL, boolean = TRUE)
        desc    Success response
        param   (int) Status code
                (object) Object sent with response
                (boolean) Json encode Object
        return  void

    error (int = 500, object = NULL, boolean = TRUE)
        desc    Error response
        param   (int) Status code
                (object) Object sent with response
                (boolean) Json encode Object
        return  void

    enable      Enable response

    disable     Disable response

    show
        desc    Standalone response
                Set HTTP response code
                Set Content-Type to application/json
                Write response data
        return  void

*******************************************************************************

    Session (Singleton)

    init        inherit
    enable      inherit
    disable     inherit
    boot        inherit

    set (string, mixed)
        desc    Set session variable
        param   (string) name of session variable
                (mixed) value of session variable
        return  TRUE if session variable was set
                FALSE otherwise

    get (string)
        desc    Retrive session variable value
        param   (string) name of session variable
        return  (mixed) value of session variable
                NULL if session variable is not set

    clear (string)
        desc    Clear session variable
        param   (string) name of session variable
        return  void

*******************************************************************************

    Auth (Singleton, Middleware)

    init        inherit
    enable      inherit
    disable     inherit
    boot        inherit

    init (array)
        desc    Initialize Auth Singleton
        param   (array) with following fields
                cookie (array)  name, value, expires, path,
                                domain, secure, httponly
                length (int) Auth token length
                characters (string) Auth token character set

    isLoggedIn
        desc    Check if a user is logged in
                Check is session variables with user id exists
                Compare session variable auth-token with auth-token cookie
        return  TRUE if valid user is logged in
                FALSE otherwise
    
    getCurrentUser
        desc    Retrive safe user record
        return  (object) if user is logged in
                NULL if no user is logged in

    setUser (object)
        desc    Set current session user
                Set session variable of user id
                Set session variable of auth-token
                Update and set auth-token cookie
        param   (object) user record
        return  TRUE if success
                FALSE if invalid param

    logout
        desc    Logout session user
                Unset session variable of user id, auth-token
                and auth-token cookie
                200 User logged out
                400 (string) "No user logged in"
        return  TRUE if success
                FALSE otherwise

    login (string, string)
        desc    Validate credentials and log user in
                Sets ResponseInterface as follows
                200 (object) Safe user object
                400 (string) "Already logged in"
                400 (string) "Email not provided"
                400 (string) "Password not provided"
                400 (string) "Incorrect password"
                400 (string) "Incorrect email"
        param   (string) email address
                (string) password
        return  (object) user object logged in
                NULL if failed to log in user

    middleware (RequestInterface, ResponseInterface, function)
        desc    Middleware to be used with Slim
                If a valid user is logged in on session, perform
                consecutive action. Otherwise respond with 401.
        return  ResponseInterface
                200 User validated, result of next action
                401 (string) "Not logged in"

*******************************************************************************

    abstract Singleton 

    init (array = NULL)
        desc    Initialize singleton
                call function boot
        return  void

    boot
        desc    Function called at end of init
        return  void

    enable      Enable object

    disable     Disable object

*******************************************************************************

    Xsrf (Singleton, Middleware)

    init        inherit
    enable      inherit
    disable     inherit
    boot        inherit

    boot
        desc    Initialize Xsrf
        return  void

    middleware (RequestInterface, ResponseInterface, function)
        desc    Middleware to be used with Slim
                Check if request needs XSRF protection
                Check if request header has valid XSRF Token
                Perform next action
        return  ResponseInterface
                200 XSRF validated, perform next action
                401 XSRF invalid or missing, not authorized

    isProtected (RequestInterface) [needsProtection]
        desc    Check if request needs XSRF protection
        return  TRUE if XSRF protection needed
                FALSE otherwise

    updateToken
        desc    Update XSRF token
        return  (string) New value of XSRF Token

    hasValidHeader (RequestInterface)
        desc    Check if request has valid XSRF Token
        return  TRUE if request has valid XSRF Token
                FALSE if invalid or missing XSRF Token

*******************************************************************************

    UserController (Controller)

    get (RequestInterface, ResponseInterface)
        desc    Retrieve current session user
        return  ResponseInterface
                200 (object) Safe user record
                200 NULL if no user is logged in

    postLogin (RequestInterface, ResponseInterface)
        desc    Log user in
        param   RequestInterface
                (string) email, required
                (string) password, required
        return  ResponseInterface
                200 (object) user: [email, username, first, last, role]
                400 (string) "Invalid email"
                400 (string) "Invalid password"
                400 (string) "Already logged in"

    postLogout (RequestInterface, ResponseInterface)
        desc    Log current session user out
        return  ResponseInterface
                200 (string) "User logged out"
                200 (string) "No user logged in"

*******************************************************************************

    SiteController (Controller)

    get (RequestInterface, ResponseInterface)
        desc    Retrieve entire Site data
        return  ResponseInterface
                200 (object) Json object of site data
                500 (string) "Could not load site due to server error"

    postSave (RequestInterface, ResponseInterface)
        desc    Save sent data
        param   RequestInerface
                (string) site, required, site data as Json string
        return  ResponseInterface
                200 (string) "Saved {bytes}"
                400 (string) "Missing site data"
                500 "Failed to save due to server error"

*******************************************************************************

    Permission (Middleware)
   
    init (array)
        desc    Initialize Permission object
        param   (array) with name of role and integer value
        return  void

    __call (string, mixed)
        desc    Middleware for Slim. Attaches permission validation to request.
        param   (RequestInterface)
                (ResponseInterface)
                (function)
        return  ResponseInterface

    hasPermission (object)
        desc    Checks if user has permission role
        param   (object) User object
        return  TRUE if user has sufficient permission
                FALSE otherwise
