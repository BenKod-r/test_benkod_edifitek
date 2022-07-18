<?php

namespace App\DataFixtures;

use App\Entity\Stack;
use App\Entity\User;
use App\Repository\StackRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppFixtures extends Fixture
{
    /** @var UserPasswordHasherInterface  */
    private UserPasswordHasherInterface $passwordHasher;

    /** @var HttpClientInterface  */
    private HttpClientInterface $client;
    /** @var StackRepository  */
    private StackRepository $stackRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher,
                                HttpClientInterface $client,
                                StackRepository $stackRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->client = $client;
        $this->stackRepository = $stackRepository;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function load(ObjectManager $manager): void
    {
        define("LASTNAME", [
            'Smith',
            'Johnson',
            'Jones',
            'Brown',
            'Miller',
            'Williams',
            'Wilson',
            'Davis',
            'Moore',
            'Taylor',
            'Anderson',
            'Rodriguez',
            'Thompson',
            'Martinez',
            'Murphy',
            'Clark',
            'Hernandez',
            'Sullivan',
            'Thomas',
            'Walker',
            'White',
            'Young',
            'Jackson',
            'Wright',
            'Garcia',
            'Gonzalez',
            'Martin',
            'Perez',
            'Harris',
            'Adams',
            'Robinson',
            'Baker',
            'Kelly',
            'Allen',
            'Bell',
            'Lee',
            'Phillips',
            'Ramirez',
            'Hall',
            'Collins',
            'Hill',
            'Murray',
            'Nelson',
            'Wood',
            'Hughes',
            'Sanchez',
            'Scott',
            'Butler',
            'Campbell',
            'Fisher',
            'Reedus',
            'Lincoln',
            'James',
            'Dean Morgan',
            'Gurira',
            'Cohan',
            'Yeun',
            'McBride',
            'Payne',
            'Bernthal',
        ]);

        define("FIRSTNAME", [
            'Norman',
            'Andrew',
            'Lennie',
            'Jeffrey',
            'Danai',
            'Lauren',
            'Steven',
            'Melissa',
            'Tom',
            'Jon',
        ]);

        define("STACKS", [
            'Tous',
            'PHP',
            'Javascript',
            'Python',
            'C#',
            'Angular',
            'Java',
            'Symfony',
            'Django',
            'Typescript',
            'Vue.js',
            'ReactJS',
            'Swift'
        ]);

        define("LETTERS", [
            'R',
            'Q',
            'L',
            'A',
            'Z',
            'M',
            'E',
            'N',
            'C',
            'G',
            'P',
            'I'
        ]);

        for ($i = 0; $i < count(constant("STACKS")); $i++) {
           $stack = new Stack();
           $stack->setName(constant("STACKS")[$i]);
           $manager->persist($stack);

        }

        $manager->flush();

        for ($t = 0; $t < 2; $t++) {
            $user = new User();
            $user
                ->setFirstName($t === 0 ? 'Developer' : 'Mentor')
                ->setLastName('Edifitek')
                ->setEmail($t === 0 ? 'developer@edifitek.com' : 'mentor@edifitek.com')
                ->setProfileImage('avatar-' . rand(1,10) . '.jpg')
                ->setCreatedAt(new \DateTime())
                ->setRoles($t === 0 ? ["ROLE_USER"] : ["ROLE_MENTOR"])
                ->setIsAvailable(rand(0,1))
            ;
            $t === 0 ?: $user->addStack($this->stackRepository->findOneBy(['name' => constant("STACKS")[rand(1, count(constant("STACKS")) - 1)]]));
            $t === 0 ?: $user->addStack($this->stackRepository->findOneBy(['name' => constant("STACKS")[rand(1, count(constant("STACKS")) - 1)]]));


            $plaintextPassword = $user->getFirstName() . '-' . $user->getLastName();
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        for ($i = 0; $i < count(constant("LETTERS")); $i++) {

            $response = $this->client->request(
                'GET',
                'https://parseapi.back4app.com/classes/Names_Letter_' . constant("LETTERS")[$i] .'?limit=10&keys=Name',
                [
                    'headers' => [
                        'X-Parse-Application-Id: zsSkPsDYTc2hmphLjjs9hz2Q3EXmnSxUyXnouj1I', // This is the fake app's application id
                        'X-Parse-Master-Key: 4LuCXgPPXXO2sU5cXm6WwpwzaKyZpo3Wpj4G4xXK' // This is the fake app's readonly master key
                    ]

                ]
            );

            for ($u = 0; $u < 10; $u++) {
                $user = new User();

                if ($response->getStatusCode() !== 200) {
                    $user->setFirstName(constant("FIRSTNAME")[rand(0, count(constant('FIRSTNAME')) - 1)]);
                } else {
                    $data = $response->toArray();
                    $user->setFirstName($data['results'][$u]['Name']);
                }

                $user
                    ->setLastName(constant("LASTNAME")[rand(0, count(constant('LASTNAME')) - 1)])
                    ->setEmail($user->getFirstName() . '-' . $user->getLastName() . '@edifitek.com')
                    ->setProfileImage('avatar-' . rand(1,10) . '.jpg')
                    ->setCreatedAt(new \DateTime());

                $i === 10 ?
                    $user
                        ->setRoles(["ROLE_MENTOR"])
                        ->setIsAvailable(rand(0,1))
                        ->addStack($this->stackRepository->findOneBy(['name' => constant("STACKS")[rand(1, count(constant("STACKS")) - 1)]]))
                        ->addStack($this->stackRepository->findOneBy(['name' => constant("STACKS")[rand(1, count(constant("STACKS")) - 1)]]))
                    :
                    $user
                        ->setRoles(["ROLE_DEVELOPER"])
                ;


                $plaintextPassword = $user->getFirstName() . '-' . $user->getLastName();
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);

                $manager->persist($user);
            }
        }


        $manager->flush();
    }
}
