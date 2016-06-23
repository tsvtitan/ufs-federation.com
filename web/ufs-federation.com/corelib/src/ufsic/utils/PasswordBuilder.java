package ufsic.utils;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import java.util.Random;

class Template {
  
  private final List<Character> source;
  private final int count;

  private static final Random random = new Random();

  public Template(List<Character> source, int count) {
    this.source = source;
    this.count = count;
  }

  public List<Character> take() {

    List<Character> taken = new ArrayList<>(count);
    for (int i = 0; i < count; i++) {
        taken.add(source.get(random.nextInt(source.size())));
    }

    return taken;
  }
}

public class PasswordBuilder {

  private final static Random random = new Random();

  // we keep our data in lists. Arrays would suffice as data never changes though.
  private final static List<Character> LOWER_CAPS, UPPER_CAPS, DIGITS, SPECIALS;

  // stores all templates
  private final List<Template> templateList = new ArrayList<>();

  // indicates if we should shuffle the password
  private boolean doShuffle;

  public static PasswordBuilder builder() {
    return new PasswordBuilder();
  }

  public PasswordBuilder lowerCase(int count) {
    templateList.add(new Template(LOWER_CAPS, count));
    return this;
  }

  public PasswordBuilder upperCase(int count) {
    templateList.add(new Template(UPPER_CAPS, count));
    return this;
  }

  public PasswordBuilder digits(int count) {
    templateList.add(new Template(DIGITS, count));
    return this;
  }

  public PasswordBuilder specials(int count) {
    templateList.add(new Template(SPECIALS, count));
    return this;
  }

  /**
   * Indicates that the password will be shuffled once
   * it's been generated.
   *
   * @return This instance.
   */
  public PasswordBuilder shuffle() {
    doShuffle = true;
    return this;
  }

  public String build() {

    // we'll use StringBuilder
    StringBuilder passwordBuilder = new StringBuilder();
    List<Character> characters = new ArrayList<Character>();

    // we want just one list containing all the characters
    for (Template template : templateList) {
        characters.addAll(template.take());
    }

    // shuffle it if user wanted that
    if (doShuffle)
        Collections.shuffle(characters);

    // can't append List<Character> or Character[], so
    // we do it one at the time
    for (char chr : characters) {
        passwordBuilder.append(chr);
    }

    return passwordBuilder.toString();
  }

  // initialize statics
  static {
    LOWER_CAPS = new ArrayList<>(26);
    UPPER_CAPS = new ArrayList<>(26);
    for (int i = 0; i < 26; i++) {
        LOWER_CAPS.add((char) (i + 'a'));
        UPPER_CAPS.add((char) (i + 'A'));
    }

    DIGITS = new ArrayList<>(10);
    for (int i = 0; i < 10; i++) {
        DIGITS.add((char) (i + '0'));
    }

    // add special characters. Note than other
    // than @, these are in ASCII range 33-43
    // so we could have used the loop as well
    SPECIALS = new ArrayList<Character>() {{
        add('!');
        add('@');
        add('#');
        add('$');
        add('%');
        add('^');
        add('&');
        add('(');
        add(')');
        add('*');
        add('+');
    }};
    
  }
}