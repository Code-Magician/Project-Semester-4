using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class ZombieController2 : MonoBehaviour
{
    public enum STATE
    {
        IDLE,
        WANDER,
        CHASE,
        ATTTACK,
        DEATH
    }
    public STATE state = STATE.IDLE;

    [Header("References")]
    public Transform target;
    [SerializeField] float damageAmount = 5f;
    [SerializeField] float walkingSpeed = 1f;
    [SerializeField] float runningSpeed = 5f;
    [SerializeField] float rotSpeed = 5f;
    [SerializeField] float approachDistance = 100f;
    [SerializeField] float forgetPlayerDistance = 110f;
    [SerializeField] float attackDistance = 3f;
    [SerializeField] Animator anim;
    [SerializeField] AudioSource AttackAudioSource;
    [SerializeField] AudioClip[] AttackClips;
    [SerializeField] GameObject ragDoll;



    bool idle, wander, chase, attack, death;
    Vector3 tempTarget = Vector3.zero;
    bool hasWanderTarget = false;
    bool isAlive = true;



    private void Update()
    {
        if (target == null)
        {
            target = GameObject.FindGameObjectWithTag("Player").gameObject.transform;
            return;
        }

        switch (state)
        {
            case STATE.IDLE:
                ToggleAnimationTriggers();
                if (!GameStats.gameOver && CanSeePlayer() && GameStats.zombieCanAttack)
                    state = STATE.CHASE;
                else if (Random.Range(0, 5000) < 10)
                    state = STATE.WANDER;
                break;
            case STATE.WANDER:
                if (hasWanderTarget)
                {
                    if (!MoveToTarget(tempTarget, 0.5f, walkingSpeed))
                        hasWanderTarget = false;
                }
                else
                {
                    tempTarget = GetNewWanderTaget();
                    if (tempTarget == Vector3.zero)
                        state = STATE.IDLE;
                    else
                    {
                        hasWanderTarget = true;
                        ToggleAnimationTriggers();
                        anim.SetBool("Walk", true);
                    }
                }
                if (!GameStats.gameOver && CanSeePlayer() && GameStats.zombieCanAttack)
                    state = STATE.CHASE;
                else if (Random.Range(0, 5000) < 10)
                {
                    state = STATE.IDLE;
                    ToggleAnimationTriggers();
                    // agent.ResetPath();
                }
                break;
            case STATE.CHASE:
                if (!GameStats.gameOver)
                {
                    if (!MoveToTarget(target.position, attackDistance, runningSpeed) && GameStats.zombieCanAttack)
                    {
                        state = STATE.ATTTACK;
                        hasWanderTarget = false;
                    }

                    if (!anim.GetBool("Run"))
                    {
                        ToggleAnimationTriggers();
                        anim.SetBool("Run", true);
                    }

                    if (InAttackRange() && GameStats.zombieCanAttack)
                    {
                        state = STATE.ATTTACK;
                        hasWanderTarget = false;
                    }
                    if (ForgetPlayer())
                    {
                        state = STATE.WANDER;
                    }
                }
                break;
            case STATE.ATTTACK:
                if (!GameStats.gameOver)
                {
                    if (!anim.GetBool("Attack"))
                    {
                        ToggleAnimationTriggers();
                        anim.SetBool("Attack", true);
                    }

                    Vector3 lookAtPos = new Vector3(target.transform.position.x, transform.position.y, target.transform.position.z);
                    this.transform.LookAt(lookAtPos);

                    if (OutOfAttackRange())
                        state = STATE.CHASE;
                }
                else
                {
                    GameOver();
                }
                break;
            case STATE.DEATH:
                ToggleAnimationTriggers();
                anim.SetBool("Death", true);

                if (isAlive)
                {
                    isAlive = false;
                    Sink sink = GetComponent<Sink>();
                    if (sink != null)
                        sink.StartSink();
                }

                break;
        }
    }

    private void GameOver()
    {
        state = STATE.WANDER;
    }

    private bool MoveToTarget(Vector3 destination, float stoppingDistance, float speed)
    {
        float dist = Vector3.Distance(transform.position, destination);
        if (!(dist >= 0 && dist <= stoppingDistance))
        {
            // Rotate
            Vector3 ourPos = new Vector3(transform.position.x, 0, transform.position.z);
            destination.y = 0;
            transform.rotation = Quaternion.Slerp(transform.rotation, Quaternion.LookRotation(destination - ourPos),
                                                    rotSpeed * Time.deltaTime);

            // Move towards player


            transform.position += transform.forward * speed * Time.deltaTime;
            return true;
        }
        else return false;
    }


    private Vector3 GetNewWanderTaget()
    {
        int count = 0;

        float x;
        if (Random.Range(0, 10) < 5)
            x = transform.position.x + 2f;
        else
            x = transform.position.x - 2f;

        float z;
        if (Random.Range(0, 10) < 5)
            z = transform.position.z + 2f;
        else
            z = transform.position.z - 2f;
        float y = transform.position.y;
        Vector3 newTarget = new Vector3(x, y, z);

        while (count <= 500)
        {
            RaycastHit hitInfo;
            Ray ray = new Ray(newTarget + new Vector3(0, 2, 0), -Vector3.up);
            if (Physics.Raycast(ray, out hitInfo, 5f))
            {
                MapLoc mLoc = hitInfo.collider.gameObject.GetComponent<MapLoc>();
                if (mLoc == null)
                {
                    Ray rayX = new Ray(transform.position + new Vector3(0, 2, 0), Vector3.right);
                    Ray raynX = new Ray(transform.position + new Vector3(0, 2, 0), -Vector3.right);
                    Ray rayZ = new Ray(transform.position + new Vector3(0, 2, 0), Vector3.forward);
                    Ray raynZ = new Ray(transform.position + new Vector3(0, 2, 0), -Vector3.back);

                    bool right = false, left = false, forward = false, backward = false;
                    if (Physics.Raycast(rayX, out hitInfo, 5f))
                    {
                        right = true;
                    }

                    if (Physics.Raycast(raynX, out hitInfo, 5f))
                    {
                        left = true;
                    }
                    if (Physics.Raycast(rayZ, out hitInfo, 5f))
                    {
                        forward = true;
                    }
                    if (Physics.Raycast(raynZ, out hitInfo, 5f))
                    {
                        backward = true;
                    }

                    if (right && left)
                    {
                        x = transform.position.x;
                        if (Random.Range(0, 10) < 5)
                            z = transform.position.z + 2f;
                        else
                            z = transform.position.z - 2f;
                    }
                    else if (forward && backward)
                    {
                        if (Random.Range(0, 10) < 5)
                            x = transform.position.x + 2f;
                        else
                            x = transform.position.x - 2f;
                        z = transform.position.z;
                    }
                    else
                    {
                        if (Random.Range(0, 10) < 5)
                            x = transform.position.x + 2f;
                        else
                            x = transform.position.x - 2f;

                        if (Random.Range(0, 10) < 5)
                            z = transform.position.z + 2f;
                        else
                            z = transform.position.z - 2f;
                    }

                    y = transform.position.y;
                    newTarget = new Vector3(x, y, z);
                }
                else
                {
                    // Ray rayX = new Ray(newTarget + new Vector3(minDistFromWall, 2, 0), -Vector3.up);
                    // Ray raynX = new Ray(newTarget + new Vector3(-minDistFromWall, 2, 0), -Vector3.up);
                    // Ray rayZ = new Ray(newTarget + new Vector3(0, 2, minDistFromWall), -Vector3.up);
                    // Ray raynZ = new Ray(newTarget + new Vector3(0, 2, -minDistFromWall), -Vector3.up);

                    // bool possible = true;
                    // if (Physics.Raycast(rayX, out hitInfo))
                    // {
                    //     mLoc = hitInfo.collider.gameObject.GetComponent<MapLoc>();
                    //     if (mLoc == null)
                    //     {
                    //         possible = false;
                    //     }
                    // }
                    // else
                    //     possible = false;
                    // if (possible && Physics.Raycast(raynX, out hitInfo))
                    // {
                    //     mLoc = hitInfo.collider.gameObject.GetComponent<MapLoc>();
                    //     if (mLoc == null)
                    //     {
                    //         possible = false;
                    //     }
                    // }
                    // else
                    //     possible = false;
                    // if (possible && Physics.Raycast(rayZ, out hitInfo))
                    // {
                    //     mLoc = hitInfo.collider.gameObject.GetComponent<MapLoc>();
                    //     if (mLoc == null)
                    //     {
                    //         possible = false;
                    //     }
                    // }
                    // else
                    //     possible = false;
                    // if (possible && Physics.Raycast(raynZ, out hitInfo))
                    // {
                    //     mLoc = hitInfo.collider.gameObject.GetComponent<MapLoc>();
                    //     if (mLoc == null)
                    //     {
                    //         possible = false;
                    //     }
                    // }
                    // else
                    //     possible = false;


                    // if (possible)
                    return newTarget;
                }
            }
            count++;
        }

        return Vector3.zero;
    }




    private void ToggleAnimationTriggers()
    {
        anim.SetBool("Walk", false);
        anim.SetBool("Run", false);
        anim.SetBool("Attack", false);
        anim.SetBool("Death", false);
    }



    bool CanSeePlayer()
    {
        RaycastHit hitInfo;
        Vector3 direction = (target.position - transform.position);
        Ray ray = new Ray(transform.position + new Vector3(0, 1, 0), direction);
        Debug.DrawRay(transform.position, direction);

        if (Vector3.Distance(this.transform.position, target.position) <= approachDistance && Physics.Raycast(ray, out hitInfo))
        {
            if (hitInfo.collider.gameObject.tag == "Player")
            {
                // Debug.Log("Can See Player");
                return true;
            }
        }

        return false;
    }

    bool ForgetPlayer()
    {
        RaycastHit hitInfo;
        Vector3 direction = (target.position - transform.position);
        Ray ray = new Ray(transform.position + new Vector3(0, 1, 0), direction);
        if (Vector3.Distance(this.transform.position, target.position) > forgetPlayerDistance)
        {
            return true;
        }
        else if (Physics.Raycast(ray, out hitInfo, 1000f))
        {
            if (hitInfo.collider.gameObject.tag != "Player")
                return true;
        }


        return false;
    }

    bool OutOfAttackRange()
    {
        RaycastHit hitInfo;
        Vector3 direction = (target.position - transform.position);
        Ray ray = new Ray(transform.position + new Vector3(0, 1, 0), direction);
        if ((Vector3.Distance(target.position, this.transform.position) > attackDistance + 2f))
        {
            return true;
        }
        else if (Physics.Raycast(ray, out hitInfo))
        {
            if (hitInfo.collider.gameObject.tag != "Player")
                return true;
        }
        return false;
    }

    bool InAttackRange()
    {
        RaycastHit hitInfo;
        Vector3 direction = (target.position - transform.position);
        Ray ray = new Ray(transform.position + new Vector3(0, 1, 0), direction);
        if ((Vector3.Distance(transform.position, target.position) <= attackDistance) && Physics.Raycast(ray, out hitInfo))
        {
            if (hitInfo.collider.gameObject.tag == "Player")
                return true;
        }

        return false;
    }

    private void DamagePlayer()
    {
        if (!GameStats.gameOver)
        {
            target.GetComponent<FPController>().TakeDamage(damageAmount);
            RandomAttackSound();
        }
        else
        {
            Invoke("GameOver", 5f);
        }
    }


    public void RandomAttackSound()
    {
        int idx = Random.Range(1, AttackClips.Length);
        AudioClip clip = AttackClips[idx];

        AttackAudioSource.Stop();
        AttackAudioSource.clip = clip;
        AttackAudioSource.Play();


        AudioClip temp = clip;
        AttackClips[idx] = AttackClips[0];
        AttackClips[0] = temp;
    }

    public void KillSelf()
    {
        float dist = Vector3.Distance(transform.position, target.position);
        if (dist <= forgetPlayerDistance)
        {
            GameObject temp = Instantiate(ragDoll, transform.position, transform.rotation);
            temp.transform.Find("Hips").GetComponent<Rigidbody>().AddForce(Camera.main.gameObject.transform.forward * Mathf.Abs(GameStats.bulletForce - 4 * dist), ForceMode.Impulse);
            Destroy(gameObject);
            return;
        }
        else
        {
            state = STATE.DEATH;
        }
    }
}
