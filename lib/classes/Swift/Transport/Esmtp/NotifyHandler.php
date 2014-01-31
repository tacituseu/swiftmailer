<?php

/**
 * An ESMTP handler for DSN support (RFC 3461).
 *
 * @package    Swift
 * @subpackage Transport
 * @author     Ryszard Wojciechowski
 */
class Swift_Transport_Esmtp_NotifyHandler implements Swift_Transport_EsmtpHandler
{
    /**
     * The types of notifications we're interested in. ('NEVER', 'SUCCESS', 'FAILURE', 'DELAY')
     *
     * @var array
     */
    private $_notifyTypes = array();

    /**
     * The ESMTP AUTH parameters available.
     *
     * @var string[]
     */
    private $_esmtpParams = array();

    /**
     * Specify the conditions under which the SMTP server should generate DSNs.
     *
     * @param array $types
     */
    public function setNotify($types)
    {
        $this->_notifyTypes = $types;
    }

    /**
     * Get the conditions under which the SMTP server should generate DSNs.
     *
     * @return array
     */
    public function getNotify()
    {
        return $this->_notifyTypes;
    }

    /**
     * Get the name of the ESMTP extension this handles.
     *
     * @return boolean
     */
    public function getHandledKeyword()
    {
        return 'DSN';
    }

    /**
     * Set the parameters which the EHLO greeting indicated.
     *
     * @param string[] $parameters
     */
    public function setKeywordParams(array $parameters)
    {
        $this->_esmtpParams = $parameters;
    }

    /**
     * Not used.
     */
    public function afterEhlo(Swift_Transport_SmtpAgent $agent)
    {
    }

    /**
     * Not used.
     */
    public function getMailParams()
    {
        return array();
    }

    /**
     * Get params which are appended to RCPT TO:<>.
     *
     * @param string $address
     *
     * @return string[]
     */
    public function getRcptParams($address)
    {
        return !empty($this->_notifyTypes) ? array('NOTIFY='.implode(',', $this->_notifyTypes), 'ORCPT=rfc822;'.$address) : array();
    }

    /**
     * Not used.
     */
    public function onCommand(Swift_Transport_SmtpAgent $agent, $command, $codes = array(), &$failedRecipients = null, &$stop = false)
    {
    }

    /**
     * Returns +1, -1 or 0 according to the rules for usort().
     *
     * This method is called to ensure extensions can be execute in an appropriate order.
     *
     * @param string $esmtpKeyword to compare with
     *
     * @return int
     */
    public function getPriorityOver($esmtpKeyword)
    {
        return 0;
    }

    /**
     * Returns an array of method names which are exposed to the Esmtp class.
     *
     * @return string[]
     */
    public function exposeMixinMethods() { return array('setNotify', 'getNotify'); }

    /**
     * Not used.
     */
    public function resetState()
    {
    }
}
