<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace DoctrineDbalUtil\DbalContrib\Event\Listeners;

use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Events;
use Doctrine\Common\EventSubscriber;

/**
 * To be able to use Foreign Keys with sqlite
 * Ispired by
 * http://www.doctrine-project.org/api/dbal/2.0/source-class-Doctrine.DBAL.Event.Listeners.OracleSessionInit.html
 * Useful doc
 * http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/events.html
 * https://symfony.com/doc/current/bundles/DoctrineBundle/configuration.html#configuration-overview
 * https://symfony.com/doc/current/service_container/tags.html#adding-additional-attributes-on-tags
 * @author Jean-Bernard Addor
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 */
class SqliteSessionInit implements EventSubscriber
{
    /**
     * @param \Doctrine\DBAL\Event\ConnectionEventArgs $args
     *
     * @return void
     */
    public function postConnect(ConnectionEventArgs $args)
    {
        $dbal = $args->getConnection();
        if ($dbal->getDatabasePlatform()->getName() == 'sqlite'):
            $dbal->exec("PRAGMA foreign_keys = ON;"); // https://github.com/doctrine/dbal/issues/2531 // should be in an event
        endif;
        // echo $dbal->getDatabasePlatform()->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(Events::postConnect);
    }
}
