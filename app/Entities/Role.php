<?php declare(strict_types=1);

namespace App\Entities;

use App\Shared\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Carbon\CarbonImmutable;

#[ORM\Entity]
#[ORM\Table(name: 'roles')]
#[ORM\HasLifecycleCallbacks]
final class Role extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;

    /**
     * The name of the role.
     *
     * @var string
     */
    #[Assert\NotBlank(message: 'Name must not be empty.')]
    #[Assert\Length(
        min: 2,
        max: 13,
        minMessage: 'The role name must be at least {{ limit }} characters long.',
        maxMessage: 'The role name cannot be longer than {{ limit }} characters.'
    )]
    #[ORM\Column(name: 'name', type: Types::STRING, length: 13)]
    private string $name;

    /**
     * The unique slug of the role.
     *
     * @var string
     */
    #[Assert\NotBlank(message: 'Slug must not be empty.')]
    #[Assert\Length(
        min: 2,
        max: 13,
        minMessage: 'The slug must be at least {{ limit }} characters long.',
        maxMessage: 'The slug cannot be longer than {{ limit }} characters.'
    )]
    #[ORM\Column(name: 'slug', type: Types::STRING, length: 13, unique: true)]
    private string $slug;

    /**
     * Timestamp when the role was created.
     *
     * @var \DateTimeImmutable
     */
    #[Assert\NotNull(message: 'Created at must not be null.')]
    #[ORM\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    /**
     * Timestamp when the role was last updated.
     *
     * @var \DateTimeImmutable
     */
    #[Assert\NotNull(message: 'Updated at must not be null.')]
    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $updatedAt;

    /**
     * The users associated with this role.
     *
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(mappedBy: 'role', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private Collection $users;

    /**
     * The permissions associated with this role.
     *
     * @var Collection<int, Permission>
     */
    #[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'roles')]
    #[ORM\JoinTable(name: 'role_permission')]
    private Collection $permissions;

    /**
     * Initializes a new role with the given details
     *
     * @param string $name
     * @param string $slug
     */
    public function __construct(string $name, string $slug)
    {
        /**
         * Generates a new role ID.
         */
        $this->id = Uuid::uuid4();

        /**
         * Sets the role's name and slug.
         */
        $this->name = $name;
        $this->slug = $slug;

        /**
         * Sets the created-at and updated-at timestamps to the current time.
         */
        $this->createdAt = CarbonImmutable::now();
        $this->updatedAt = CarbonImmutable::now();


        /**
         * Initializes an empty collection for users.
         */
        $this->users = new ArrayCollection();

        /**
         * Initializes an empty collection for permissions.
         */
        $this->permissions = new ArrayCollection();
    }

    /**
     * Get the ID of the role.
     *
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * Set the name of the role.
     *
     * @param string $name The name of the role.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the name of the role.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the slug of the role.
     *
     * @param string $slug The unique slug of the role.
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * Get the slug of the role.
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Get the date and time when the role was created.
     *
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Update the date and time when the role was last updated.
     */
    #[ORM\PreUpdate]
    public function setUpdatedAtOnUpdate(): void
    {
        $this->updatedAt = CarbonImmutable::now();
    }

    /**
     * Get the date and time when the role was last updated.
     *
     * @return \DateTimeImmutable
     */
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Add a user to the role.
     *
     * @param User $user The user to add.
     */
    public function addUser(User $user): void
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setRole($this);
        }
    }

    /**
     * Get the users associated with this role.
     *
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * Remove a user from the role.
     *
     * @param User $user The user to remove.
     */
    public function removeUser(User $user): void
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->setRole(role: null);
        }
    }

    /**
     * Add a permission to the role.
     *
     * @param Permission $permission The permission to add.
     */
    public function addPermissions(Permission $permission): void
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
            $permission->addRoles(role: $this);
        }
    }

    /**
     * Get the permissions associated with this role.
     *
     * @return Collection<int, Permission>
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    /**
     * Remove a permission from the role.
     *
     * @param Permission $permission The permission to remove.
     */
    public function removePermissions(Permission $permission): void
    {
        if ($this->permissions->contains($permission)) {
            $this->permissions->removeElement($permission);
            $permission->removeRoles(role: $this);
        }
    }
}
